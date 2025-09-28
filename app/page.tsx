import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { CheckmateUpload } from "@/components/checkmate-upload"

export default function Page() {
  return (
    <main className="min-h-dvh flex items-center justify-center p-6">
      <div className="w-full max-w-3xl">
        <Card className="bg-card text-card-foreground">
          <CardHeader>
            <CardTitle className="text-pretty">CheckMate – Legal Rule & Document Verification System</CardTitle>
            <CardDescription className="text-muted-foreground">
              Upload a certificate (PDF/image) and enter the student&apos;s full name to verify authenticity and
              eligibility using discrete mathematics (propositional logic, set theory, predicate logic, and truth
              table).
            </CardDescription>
          </CardHeader>
          <CardContent>
            <CheckmateUpload />
          </CardContent>
        </Card>
      </div>
    </main>
  )
}
