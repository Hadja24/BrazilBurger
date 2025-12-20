import { Header } from './components/Header';
import { HeroSection } from './components/HeroSection';
import { PopularItems } from './components/PopularItems';
import { PromoSection } from './components/PromoSection';
import { MenuSection } from './components/MenuSection';
import { ContactSection } from './components/ContactSection';
import { Footer } from './components/Footer';

export default function App() {
  return (
    <div className="min-h-screen bg-white">
      <Header />
      <main>
        <HeroSection />
        <PopularItems />
        <PromoSection />
        <MenuSection />
        <ContactSection />
      </main>
      <Footer />
    </div>
  );
}
