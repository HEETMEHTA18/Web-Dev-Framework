export default function ContactFooter() {
  return (
    <footer id="contact" className="pv-section pv-footer">
      <div className="pv-container">
        <h2>Let's work together</h2>
        <form className="pv-form" onSubmit={(e) => e.preventDefault()}>
          <div className="pv-form-row">
            <input placeholder="Name" />
            <input placeholder="Email" />
          </div>
          <div className="pv-form-row">
            <select defaultValue="">
              <option value="" disabled>Service Needed ?</option>
              <option>Branding</option>
              <option>Web design</option>
              <option>Web Design</option>
              <option>UI / UX</option>
            </select>
          </div>
          <textarea placeholder="What Can I Help You..."></textarea>
          <button className="pv-btn pv-btn-primary" type="submit">Submit</button>
        </form>
        <div className="pv-footer-meta">
          <div>Email : designer@example.com</div>
          <div>Call Today : +1 (555) 123-4567</div>
          <div>Social : —</div>
        </div>
        <div className="pv-copy">© Copyright 2025. All Rights Reserved.</div>
      </div>
    </footer>
  )
}


