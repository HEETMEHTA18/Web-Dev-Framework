export default function Services() {
  const items = [
    { title: 'ui/ux design', points: ['Wireframing and prototyping', 'UI for web & mobile', 'Usability testing', 'Micro-interactions'] },
    { title: 'Graphic Design', points: ['Logo & brand identity', 'Social media creatives', 'Infographics', 'Illustrations & icons'] },
    { title: 'Web Design', points: ['Responsive websites', 'Landing pages', 'Webflow customization', 'Maintenance & updates'] },
    { title: 'Branding', points: ['Brand strategy', 'Style guides', 'Typography & colors', 'Storytelling & messaging'] },
  ]
  return (
    <section id="services" className="pv-section">
      <div className="pv-container">
        <div className="pv-section-head">
          <h2>what I can do for you</h2>
          <p>As a digital designer, I craft experiences that connect deeply and spark creativity.</p>
        </div>
        <div className="pv-grid">
          {items.map((it) => (
            <div key={it.title} className="pv-card">
              <h3>{it.title}</h3>
              <ul>
                {it.points.map((p) => (<li key={p}>{p}</li>))}
              </ul>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}


