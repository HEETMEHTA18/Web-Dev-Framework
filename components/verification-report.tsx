import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Separator } from "@/components/ui/separator"

type VerificationResult = {
  ownerStatus: { pass: boolean; detail: string }
  databaseValidation: {
    pass: boolean
    method: "id" | "hash" | "name" | "none"
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

export function VerificationReport({ result, inputName }: { result: VerificationResult; inputName: string }) {
  const decisionOk = result.finalDecision === "allowed"

  return (
    <div className="flex flex-col gap-6">
      <Card className="bg-card">
        <CardHeader>
          <CardTitle className="text-pretty">Verification Report</CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          {/* High-level summary */}
          <div className="flex flex-wrap items-center gap-3">
            <Badge variant="outline" className="border-border">
              Owner Status: {result.ownerStatus.pass ? "Verified Match" : "Mismatch"}
            </Badge>
            <Badge variant="outline" className="border-border">
              Authenticity: {result.authenticity.pass ? "Genuine Certificate" : "Not Genuine"}
            </Badge>
            <Badge variant="outline" className="border-border">
              Database: {result.databaseValidation.pass ? "Valid Entry Found" : "Not Found"}
            </Badge>
            <Badge
              className={
                decisionOk ? "bg-primary text-primary-foreground" : "bg-destructive text-destructive-foreground"
              }
            >
              Final Admission Decision: {decisionOk ? "Allowed ✅" : "Denied ❌"}
            </Badge>
          </div>

          <Separator className="bg-border" />

          {/* Step-by-step details */}
          <section className="space-y-2">
            <h3 className="font-semibold">Introduction</h3>
            <p className="text-sm text-muted-foreground">
              This verification ensures the uploaded document is authentic, belongs to the claimed owner, exists in the
              reference database, and that the candidate satisfies eligibility. We employ Discrete Mathematics:
              propositional logic, set theory, predicate logic, and Boolean algebra.
            </p>
          </section>

          <section className="space-y-2">
            <h3 className="font-semibold">Methodology</h3>
            <ul className="text-sm text-muted-foreground list-disc pl-5 space-y-1">
              <li>Data Collection: user-provided name and uploaded PDF/image.</li>
              <li>Preprocessing: normalize names (case-insensitive, order-insensitive token sorting).</li>
              <li>Clause Checking: Name check (Propositional Logic) and membership (Set Theory).</li>
              <li>Eligibility: Check stored eligibility of matching certificate (Predicate Logic).</li>
              <li>Validation: Optional hash verification; Truth table evaluation for final decision.</li>
            </ul>
          </section>

          <section className="space-y-2">
            <h3 className="font-semibold">Results</h3>
            <div className="text-sm space-y-2">
              <p>
                <strong>Name Check (Propositional Logic)</strong> → {result.ownerStatus.pass ? "Pass" : "Fail"} —{" "}
                {result.ownerStatus.detail}
              </p>
              <p>
                <strong>Database Match (Set Theory)</strong> → {result.databaseValidation.pass ? "Pass" : "Fail"} —{" "}
                {result.databaseValidation.detail}
              </p>
              <p>
                <strong>Eligibility Check (Predicate Logic)</strong> → {result.predicateLogic.pass ? "Pass" : "Fail"} —{" "}
                {result.predicateLogic.detail}
              </p>
              <p>
                <strong>Hash Check</strong> →{" "}
                {result.hashVerification.status === "verified"
                  ? "Verified ✓"
                  : result.hashVerification.status === "warning"
                    ? "Warning ⚠ (no original hash on record)"
                    : result.hashVerification.status === "not_provided"
                      ? "Not Provided"
                      : "Mismatch ✗"}
                {result.hashVerification.computedHash ? (
                  <span className="block text-xs text-muted-foreground break-all">
                    Hash(cert) = {result.hashVerification.computedHash}
                    {result.hashVerification.expectedHash
                      ? `; Hash(original) = ${result.hashVerification.expectedHash}`
                      : ""}
                  </span>
                ) : null}
              </p>
              <div className="overflow-x-auto">
                <table className="w-full text-sm border border-border rounded-md">
                  <thead>
                    <tr className="bg-muted">
                      <th className="px-3 py-2 text-left">A (Authenticity)</th>
                      <th className="px-3 py-2 text-left">E (Eligibility)</th>
                      <th className="px-3 py-2 text-left">Y (Admission Allowed = A ∧ E)</th>
                    </tr>
                  </thead>
                  <tbody>
                    {result.truthTable.table.map((row, idx) => (
                      <tr key={idx} className="border-t border-border">
                        <td className="px-3 py-2">{row.A}</td>
                        <td className="px-3 py-2">{row.E}</td>
                        <td className="px-3 py-2">{row.Y}</td>
                      </tr>
                    ))}
                    <tr className="border-t border-border bg-accent">
                      <td className="px-3 py-2 font-semibold">A = {result.truthTable.A}</td>
                      <td className="px-3 py-2 font-semibold">E = {result.truthTable.E}</td>
                      <td className="px-3 py-2 font-semibold">Y = {result.truthTable.Y}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <section className="space-y-2">
            <h3 className="font-semibold">Conclusion</h3>
            <p className="text-sm text-muted-foreground">
              Final Decision: Admission = A ∧ E → {result.truthTable.A} ∧ {result.truthTable.E} = {result.truthTable.Y}{" "}
              —{" "}
              <span className={decisionOk ? "text-primary" : "text-destructive"}>
                {decisionOk ? "Admission Allowed ✅" : "Admission Denied ❌"}
              </span>
            </p>
          </section>

          <section className="space-y-2">
            <h3 className="font-semibold">References</h3>
            <ul className="text-sm text-muted-foreground list-disc pl-5 space-y-1">
              <li>Propositional Logic and Boolean Algebra</li>
              <li>Set Theory (membership, element-of relation)</li>
              <li>Predicate Logic (universal implication for eligibility)</li>
            </ul>
          </section>
        </CardContent>
      </Card>

      {/* Output-style summary lines to mirror the provided example */}
      <Card className="bg-card">
        <CardHeader>
          <CardTitle className="text-pretty">Formatted Output</CardTitle>
        </CardHeader>
        <CardContent className="space-y-1 text-sm">
          <p>Verification {result.truthTable.Y === 1 ? "Successful" : "Unsuccessful"}...</p>
          <p>Mathematical verification completed</p>
          <p>Owner Status: {result.ownerStatus.pass ? "Verified Match..." : "Mismatch..."}</p>
          <p>Authenticity: {result.authenticity.pass ? "Genuine Certificate..." : "Not Genuine..."}</p>
          <p>Database: {result.databaseValidation.pass ? "Valid Entry Found" : "No Matching Entry"}</p>
          <p>
            Propositional Logic: {result.ownerStatus.pass ? "Passed" : "Failed"} → {result.normalizedNames.input}
            {result.normalizedNames.certificate ? ` = ${result.normalizedNames.certificate}` : ""}{" "}
            {result.ownerStatus.pass ? "✓" : "✗"}
          </p>
          <p>
            Set Theory: {result.databaseValidation.pass ? "Passed" : "Failed"} →{" "}
            {result.databaseValidation.certificate
              ? `Certificate(${result.databaseValidation.certificate.id}) ∈ ValidCertificates`
              : "Certificate ∉ ValidCertificates"}{" "}
            {result.databaseValidation.pass ? "✓" : "✗"}
          </p>
          <p>
            Predicate Logic: {result.predicateLogic.pass ? "Passed" : "Failed"} → ∀c (Valid(c) ∧ Owner(c)) → Eligible(c){" "}
            {result.predicateLogic.pass ? "✓" : "✗"}
          </p>
          <p>
            Hash Verification:{" "}
            {result.hashVerification.status === "verified"
              ? "Verified"
              : result.hashVerification.status === "warning"
                ? "Warning"
                : result.hashVerification.status === "not_provided"
                  ? "Not Provided"
                  : "Mismatch"}{" "}
            {result.hashVerification.status === "verified"
              ? "✓"
              : result.hashVerification.status === "warning"
                ? "⚠"
                : "✗"}
          </p>
          <p>
            Truth Table Evaluation: A={result.truthTable.A}, E={result.truthTable.E} → Y={result.truthTable.Y}
          </p>
          <p>Final Decision: Admission {result.truthTable.Y ? "Allowed ✅" : "Denied ❌"}</p>
        </CardContent>
      </Card>
    </div>
  )
}
