export default function About() {
  const stats = [
    { k: 'Years of Experience', v: '0' },
    { k: 'Completed Projects', v: '0' },
    { k: 'Clients on Worldwide', v: '0+' },
  ]
  return (
    <section id="about" className="pv-section">
      <div className="pv-container">
        <h2>About me</h2>
        <p>Hi, I'm Duncan — a digital designer and Framer developer passionate about crafting meaningful digital experiences.</p>
        <div className="pv-stats">
          {stats.map((s) => (
            <div key={s.k} className="pv-stat">
              <div className="pv-stat-v">{s.v}</div>
              <div className="pv-stat-k">{s.k}</div>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}


