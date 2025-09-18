export default function Projects() {
  const items = [
    { cat: 'Graphic Design', title: 'Summer Vibes Festival Campaign' },
    { cat: 'Branding', title: 'Coral Spiral Abstract' },
    { cat: 'UI / UX Design', title: 'ShopEase Redesign Sprint' },
    { cat: 'Branding', title: 'Black Geometric Prisms' },
  ]
  return (
    <section id="projects" className="pv-section">
      <div className="pv-container">
        <h2>Featured Projects</h2>
        <div className="pv-grid">
          {items.map((it) => (
            <article key={it.title} className="pv-card pv-project">
              <div className="pv-project-cat">{it.cat}</div>
              <h3>{it.title}</h3>
              <a className="pv-btn pv-btn-ghost" href="#">View</a>
            </article>
          ))}
        </div>
        <div style={{ marginTop: 24 }}>
          <a className="pv-btn pv-btn-primary" href="#">Browse All Projects</a>
        </div>
      </div>
    </section>
  )
}


