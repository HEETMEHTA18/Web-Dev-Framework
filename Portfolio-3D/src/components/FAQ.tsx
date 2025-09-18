import { useState } from 'react'

const faqs = [
  'What services do you offer?',
  'How does the design process work?',
  'How long does a project usually take?',
  'What do I need to provide before starting a project?',
  'Do you offer revisions?',
  'How do I get started?',
]

export default function FAQ() {
  const [open, setOpen] = useState<number | null>(0)
  return (
    <section id="faq" className="pv-section">
      <div className="pv-container">
        <h2>Frequently Asked Questions</h2>
        <div className="pv-accordion">
          {faqs.map((q, i) => (
            <div key={q} className={`pv-acc-item ${open === i ? 'open' : ''}`}>
              <button className="pv-acc-btn" onClick={() => setOpen(open === i ? null : i)}>{i + 1}. {q}</button>
              <div className="pv-acc-content">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque habitant morbi tristique.</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}


