export default function Testimonials() {
  const items = [
    { name: 'John Harris', role: 'Marketing Director', quote: 'Duncan truly understood my vision and turned it into impactful designs.' },
    { name: 'Michael Lee', role: 'Product Manager', quote: 'He delivered a design that resonated perfectly with our audience.' },
    { name: 'Sarah Johnson', role: 'CEO', quote: 'Transformed my ideas into a high-performing, visually striking website.' },
    { name: 'Laura Bennett', role: 'Small Business Owner', quote: 'Made the process stress-free and efficient.' },
  ]
  return (
    <section id="testimonials" className="pv-section">
      <div className="pv-container">
        <h2>What My Clients Say</h2>
        <div className="pv-grid">
          {items.map((t) => (
            <blockquote key={t.name} className="pv-card pv-quote">
              <p>“{t.quote}”</p>
              <footer>
                <strong>{t.name}</strong><span> — {t.role}</span>
              </footer>
            </blockquote>
          ))}
        </div>
      </div>
    </section>
  )
}


