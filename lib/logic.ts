import { validCertificates, normalizeName, type Certificate } from "./sample-db"

export type VerifyInput = {
  name: string
  file?: { bytes: ArrayBuffer; filename?: string } | null
}

export type VerifyOutput = {
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
  truthTable: { A: 0 | 1; E: 0 | 1; Y: 0 | 1; table: Array<{ A: 0 | 1; E: 0 | 1; Y: 0 | 1 }> }
  finalDecision: "allowed" | "denied"
  normalizedNames: { input: string; certificate?: string }
}

function toHex(ab: ArrayBuffer) {
  const bytes = new Uint8Array(ab)
  let hex = ""
  for (let i = 0; i < bytes.length; i++) {
    hex += bytes[i].toString(16).padStart(2, "0")
  }
  return hex
}

async function sha256Hex(data: ArrayBuffer | Uint8Array): Promise<string | undefined> {
  try {
    const uint8 = data instanceof Uint8Array ? data : new Uint8Array(data)
    if (globalThis.crypto?.subtle) {
      const digest = await globalThis.crypto.subtle.digest("SHA-256", uint8)
      return toHex(digest)
    }
  } catch {
    // fall through to Node fallback
  }
  try {
    // dynamic import to avoid bundling errors if Node crypto is unavailable
    const { createHash } = await import("crypto")
    const hash = createHash("sha256")
    // Node createHash accepts Uint8Array directly
    hash.update(data instanceof Uint8Array ? data : new Uint8Array(data))
    return hash.digest("hex")
  } catch {
    // hashing not available in this environment; treat as optional
    return undefined
  }
}

function extractCertIdFromFilename(filename?: string | null): string | null {
  if (!filename) return null
  const upper = filename.toUpperCase()
  const m = upper.match(/CERT-[A-Z0-9]+-\d{8}/)
  return m ? m[0] : null
}

export async function verifyDocument(input: VerifyInput): Promise<VerifyOutput> {
  // Normalize names (propositional logic equality check, order-insensitive)
  const normInput = normalizeName(input.name || "")
  const namePassDetail = (c: Certificate | null) =>
    c ? `${normInput} ${c ? "=" : "≠"} ${c?.name ?? ""}`.trim() : `${normInput} compared with database record name`

  // Compute file hash if provided (optional)
  let computedHash: string | undefined
  if (input.file?.bytes) {
    computedHash = await sha256Hex(input.file.bytes)
  }

  let matched: Certificate | null = null
  let method: "id" | "hash" | "name" | "none" = "none"

  const parsedId = extractCertIdFromFilename(input.file?.filename)
  if (parsedId) {
    matched = validCertificates.find((c) => c.id.toUpperCase() === parsedId) || null
    if (matched) method = "id"
  }

  if (!matched && computedHash) {
    matched = validCertificates.find((c) => c.hash && c.hash.toLowerCase() === computedHash!.toLowerCase()) || null
    if (matched) method = "hash"
  }

  if (!matched) {
    matched = validCertificates.find((c) => c.name === normInput) || null
    if (matched) method = "name"
  }

  // Owner status (propositional logic equality of names)
  const ownerPass = !!matched ? matched.name === normInput : false
  const ownerStatus = {
    pass: ownerPass,
    detail: !!matched ? `${normInput} = ${matched.name}` : `${normInput} (no exact owner match on record)`,
  }

  // Hash verification
  let hashStatus: VerifyOutput["hashVerification"]["status"] = "not_provided"
  let expectedHash: string | undefined = undefined
  if (computedHash) {
    if (matched?.hash) {
      expectedHash = matched.hash
      hashStatus = matched.hash.toLowerCase() === computedHash.toLowerCase() ? "verified" : "mismatch"
    } else {
      hashStatus = "warning" // optional: no known original hash
    }
  }

  // Authenticity A: database match AND (hash verified OR no expected hash recorded)
  const authenticityPass =
    !!matched && (hashStatus === "verified" || hashStatus === "warning" || hashStatus === "not_provided")
  const authenticity = {
    pass: authenticityPass,
    reason: matched
      ? hashStatus === "verified"
        ? "Database match and hash verified"
        : matched.hash
          ? "Database match but hash mismatch"
          : "Database match; original hash not available"
      : "No database match",
  }

  // Predicate logic: ∀c (Valid(c) ∧ Owner(c)) → Eligible(c)
  const eligible = matched?.eligibility ?? false
  const predicatePass = matched ? !(true && ownerPass) || eligible : false
  const predicateLogic = {
    pass: predicatePass,
    detail: `For certificate c: Valid(c)=${!!matched}, Owner(c)=${ownerPass} ⇒ Eligible(c)=${eligible}`,
    eligible,
  }

  // Truth table: Y = A ∧ E
  const A: 0 | 1 = authenticityPass ? 1 : 0
  const E: 0 | 1 = eligible ? 1 : 0
  const Y: 0 | 1 = (A && E) as 0 | 1

  const truthTable = {
    A,
    E,
    Y,
    table: [
      { A: 0, E: 0, Y: 0 },
      { A: 0, E: 1, Y: 0 },
      { A: 1, E: 0, Y: 0 },
      { A: 1, E: 1, Y: 1 },
    ],
  }

  const databaseValidation = {
    pass: !!matched,
    method,
    detail: matched
      ? method === "id"
        ? "Matched by certificate ID parsed from filename against ValidCertificates"
        : method === "hash"
          ? "Matched by hash against ValidCertificates"
          : "Matched by normalized owner name against ValidCertificates"
      : "No match found in ValidCertificates",
    certificate: matched ? { id: matched.id, name: matched.name } : null,
  }

  return {
    ownerStatus,
    databaseValidation,
    hashVerification: { status: hashStatus, computedHash, expectedHash },
    authenticity,
    predicateLogic,
    truthTable,
    finalDecision: Y === 1 ? "allowed" : "denied",
    normalizedNames: { input: normInput, certificate: matched?.name },
  }
}
