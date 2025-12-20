import { Clock, Facebook, Instagram, Youtube, Search, ShoppingBag, Menu } from 'lucide-react';
import imgImage1 from "figma:asset/6fac4e166abba0bcba4bda4b6180badfdaa54088.png";
import imgImage2 from "figma:asset/21001225ecb7c4179ede45ac5c7aaeb838fbc1c2.png";
import imgImage3 from "figma:asset/1106dde10efaa1dd7b0bb1cd2f2ae3f08d55b6e8.png";
import imgImage4 from "figma:asset/b1ab26f08be7f48e930a6fd3c5ab0afab6059583.png";
import imgImage6 from "figma:asset/3209992f61523635b23cc90d811adc6448da395d.png";
import imgImage7 from "figma:asset/68b11f2a4265485763967048bc9072e897d4ad74.png";
import imgImage8 from "figma:asset/948889874b0f5ce96e22e67defd19d19ed286fdd.png";
import imgImage9 from "figma:asset/a02e9f37f0bbd918258ede9bb6596913c8dc6dd9.png";

export function Header() {
  return (
    <header className="relative">
      {/* Top Bar */}
      <div className="bg-[#F30C0C] py-2">
        <div className="container mx-auto px-4 flex items-center justify-between text-white text-sm">
          <div className="flex items-center gap-2">
            <img src={imgImage2} alt="" className="w-6 h-6" />
            <span>09:00 am - 06:00 pm</span>
          </div>
          <div className="flex items-center gap-4">
            <span>Follow Us:</span>
            <div className="flex items-center gap-3">
              <img src={imgImage3} alt="Facebook" className="w-5 h-5 cursor-pointer hover:opacity-80 transition-opacity" />
              <img src={imgImage4} alt="Instagram" className="w-5 h-5 cursor-pointer hover:opacity-80 transition-opacity" />
              <img src={imgImage6} alt="YouTube" className="w-5 h-5 cursor-pointer hover:opacity-80 transition-opacity" />
            </div>
          </div>
        </div>
      </div>

      {/* Main Navigation */}
      <div className="bg-black py-6">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between">
            {/* Logo */}
            <div className="flex-shrink-0">
              <img src={imgImage1} alt="Brazil Burger" className="h-16 w-auto" />
            </div>

            {/* Navigation Links */}
            <nav className="hidden lg:flex items-center gap-12 text-white font-extrabold text-xl">
              <a href="#home" className="hover:text-[#FAC42F] transition-colors">Home</a>
              <a href="#menu" className="hover:text-[#FAC42F] transition-colors">Menu</a>
              <a href="#pages" className="hover:text-[#FAC42F] transition-colors">Pages</a>
              <a href="#blog" className="hover:text-[#FAC42F] transition-colors">Blog</a>
              <a href="#contact" className="hover:text-[#FAC42F] transition-colors">Contact Us</a>
            </nav>

            {/* Right Side Actions */}
            <div className="flex items-center gap-4">
              <img src={imgImage7} alt="Search" className="w-6 h-6 opacity-70 cursor-pointer hover:opacity-100 transition-opacity hidden md:block" />
              <img src={imgImage8} alt="Cart" className="w-6 h-6 opacity-80 cursor-pointer hover:opacity-100 transition-opacity hidden md:block" />
              
              <button className="bg-[#E81717] text-white px-6 py-3 flex items-center gap-2 hover:bg-[#c91414] transition-colors">
                <span className="text-sm">ORDER NOW</span>
                <img src={imgImage9} alt="" className="w-6 h-6" />
              </button>

              {/* Mobile Menu */}
              <button className="lg:hidden text-white">
                <div className="flex flex-col gap-1">
                  <div className="w-8 h-1 bg-white/80 rounded"></div>
                  <div className="w-8 h-1 bg-white/80 rounded"></div>
                  <div className="w-8 h-1 bg-white/80 rounded"></div>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </header>
  );
}
