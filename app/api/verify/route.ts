import { type NextRequest, NextResponse } from "next/server"
import { verifyDocument } from "@/lib/logic"

export async function POST(req: NextRequest) {
  try {
    const form = await req.formData()
    const name = (form.get("name") as string) || ""
    const file = form.get("file") as File | null

    let bytes: ArrayBuffer | null = null
    let filename: string | undefined = undefined

    if (file) {
      bytes = await file.arrayBuffer()
      filename = file.name
    }

    const result = await verifyDocument({
      name,
      file: bytes ? { bytes, filename } : null,
    })

    return NextResponse.json(result)
  } catch (err: any) {
    // Return readable error to the client
    return new NextResponse(err?.message ?? "Verification failed", { status: 400 })
  }
}
