import { useEffect } from 'react'
import Lenis from 'lenis'
import Hero from './components/Hero'
import Services from './components/Services'
import About from './components/About'
import Projects from './components/Projects'
import Testimonials from './components/Testimonials'
import FAQ from './components/FAQ'
import ContactFooter from './components/ContactFooter'

function useSmoothScroll() {
  useEffect(() => {
    const lenis = new Lenis({ smoothWheel: true })
    const raf = (t: number) => { lenis.raf(t); requestAnimationFrame(raf) }
    requestAnimationFrame(raf)
    return () => lenis.destroy()
  }, [])
}

export default function App() {
  useSmoothScroll()
  return (
    <>
      <nav className="nav">
        <a href="#home" className="hud-button">Home</a>
        <a href="#services" className="hud-button">Services</a>
        <a href="#about" className="hud-button">About</a>
        <a href="#projects" className="hud-button">Projects</a>
        <a href="#testimonials" className="hud-button">Testimonials</a>
        <a href="#faq" className="hud-button">FAQ</a>
        <a href="#contact" className="hud-button">Contact</a>
      </nav>
      <Hero />
      <Services />
      <About />
      <Projects />
      <Testimonials />
      <FAQ />
      <ContactFooter />
    </>
  )
}
