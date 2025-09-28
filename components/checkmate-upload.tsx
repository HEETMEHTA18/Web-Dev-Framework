"use client"

import type * as React from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Separator } from "@/components/ui/separator"
import { useState } from "react"
import { VerificationReport } from "@/components/verification-report"
import { cn } from "@/lib/utils"

type VerificationResult = {
  ownerStatus: { pass: boolean; detail: string }
  databaseValidation: {
    pass: boolean
    method: "hash" | "name" | "none"
    detail: string
    certificate?: { id: string; name: string } | null
  }
  hashVerification: {
    status: "verified" | "warning" | "mismatch" | "not_provided"
    computedHash?: string
    expectedHash?: string
  }
  authenticity: { pass: boolean; reason: string }
  predicateLogic: { pass: boolean; detail: string; eligible: boolean }
  truthTable: {
    A: 0 | 1
    E: 0 | 1
    Y: 0 | 1
    table: Array<{ A: 0 | 1; E: 0 | 1; Y: 0 | 1 }>
  }
  finalDecision: "allowed" | "denied"
  normalizedNames: { input: string; certificate?: string }
}

export function CheckmateUpload() {
  const [file, setFile] = useState<File | null>(null)
  const [name, setName] = useState("")
  const [loading, setLoading] = useState(false)
  const [result, setResult] = useState<VerificationResult | null>(null)
  const [error, setError] = useState<string | null>(null)

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault()
    setError(null)
    setResult(null)

    if (!file) {
      setError("Please select a certificate file (PDF or image).")
      return
    }
    if (!name.trim()) {
      setError("Please enter the student's full name.")
      return
    }

    try {
      setLoading(true)
      const form = new FormData()
      form.append("file", file)
      form.append("name", name)

      const res = await fetch("/api/verify", {
        method: "POST",
        body: form,
      })
      if (!res.ok) {
        const text = await res.text()
        throw new Error(text || "Verification failed.")
      }
      const data = (await res.json()) as VerificationResult
      setResult(data)
    } catch (err: any) {
      setError(err?.message ?? "Something went wrong.")
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="flex flex-col gap-6">
      <form onSubmit={onSubmit} className="flex flex-col gap-4">
        <div className="flex flex-col gap-2">
          <Label htmlFor="name">Student Full Name</Label>
          <Input
            id="name"
            placeholder="e.g., HEET HITESH MEHTA"
            value={name}
            onChange={(e) => setName(e.target.value)}
            className="bg-background"
            required
          />
        </div>

        <div className="flex flex-col gap-2">
          <Label htmlFor="file">Certificate File (PDF or Image)</Label>
          <Input
            id="file"
            type="file"
            accept="application/pdf,image/*"
            onChange={(e) => setFile(e.target.files?.[0] ?? null)}
            className="bg-background"
            required
          />
          <p className="text-xs text-muted-foreground">
            Tip: If your certificate has a known ID like {"CERT-XXXX-YYYYMMDD"}, include it in the file name for
            clarity.
          </p>
        </div>

        <div className="flex items-center gap-3">
          <Button type="submit" disabled={loading} className={cn(loading && "opacity-70")}>
            {loading ? "Verifying..." : "Verify Document"}
          </Button>
          {error ? <span className="text-destructive text-sm">{error}</span> : null}
        </div>
      </form>

      <Separator className="bg-border" />

      {result ? <VerificationReport result={result} inputName={name} /> : null}
    </div>
  )
}
