import { motion } from 'framer-motion'

export default function Hero() {
  return (
    <header id="home" className="pv-hero">
      <div className="pv-container pv-hero-inner">
        <motion.p initial={{ opacity: 0, y: 10 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.6 }} className="pv-eyebrow">Hi</motion.p>
        <motion.h1 initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.8, delay: 0.05 }} className="pv-title">
          Duncan Robert
          <span className="pv-hash"># digital</span>
          <span className="pv-hash"># designer</span>
        </motion.h1>
        <motion.p initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ duration: 0.8, delay: 0.15 }} className="pv-subtitle">
          I'm a US-based digital designer and Framer developer
        </motion.p>
        <div className="pv-cta">
          <a href="#projects" className="pv-btn pv-btn-primary">Browse Projects</a>
          <a href="#contact" className="pv-btn">Contact</a>
        </div>
      </div>
    </header>
  )
}


