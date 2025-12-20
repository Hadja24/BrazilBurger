import imgImage16 from "figma:asset/bc1d0a3333833c2ddf466e539ef24ad50edce8d1.png";
import imgImage18 from "figma:asset/d604082d5912d2051134e964cb0656ff31b17b0d.png";
import imgRectangle16 from "figma:asset/b5c2283e5999eb90564ac7f680d86c1f1da04a0.png";
import imgImage4 from "figma:asset/b1ab26f08be7f48e930a6fd3c5ab0afab6059583.png";
import imgImage17 from "figma:asset/04bae8d31bc816880a60d36640992e343ea121d4.png";
import imgImage19 from "figma:asset/ae282a2a3a9396671fc8c77dd934b51dc4fc67c9.png";
import imgImage23 from "figma:asset/f5dd990d6ae392971d7be7339155c0802bcc9150.png";

export function Footer() {
  return (
    <footer className="bg-[#313030] border-t border-black">
      {/* Main Footer */}
      <div className="container mx-auto px-4 py-16">
        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-12">
          {/* Brand Section */}
          <div>
            <img src={imgImage16} alt="Brazil Burger" className="h-20 w-auto mb-6" />
            <p className="text-white/90 text-lg leading-relaxed mb-8">
              Brazil Burger — A creation inspired by Brazilian flavors, combining toasted bread, 
              juicy beef, Brazilian salad, breaded mozzarella, special mayo, and halal bacon. 
              An original blend designed to surprise, even within a diverse menu.
            </p>
            
            {/* Social Media */}
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 border border-white/27 flex items-center justify-center hover:bg-white/10 transition-colors cursor-pointer">
                <img src={imgRectangle16} alt="Facebook" className="w-full h-full object-cover" />
              </div>
              <div className="w-12 h-12 border border-white/27 flex items-center justify-center hover:bg-white/10 transition-colors cursor-pointer">
                <img src={imgImage4} alt="Instagram" className="w-9 h-9" />
              </div>
              <div className="w-12 h-12 border border-white/27 flex items-center justify-center hover:bg-white/10 transition-colors cursor-pointer">
                <img src={imgImage17} alt="Twitter" className="w-8 h-8" />
              </div>
              <div className="w-12 h-12 border border-white/27 flex items-center justify-center hover:bg-white/10 transition-colors cursor-pointer">
                <img src={imgImage19} alt="LinkedIn" className="w-9 h-9" />
              </div>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="text-white font-extrabold text-4xl mb-6">Quick Links</h3>
            <div className="h-0.5 w-14 bg-[#FAC42F] mb-8"></div>
            <ul className="space-y-4">
              <li>
                <a href="#contact" className="text-white/90 text-2xl font-light hover:text-[#FAC42F] transition-colors flex items-center gap-3">
                  <img src={imgImage18} alt="" className="w-6 h-6" />
                  Contact Us
                </a>
              </li>
              <li>
                <a href="#blog" className="text-white/90 text-2xl font-light hover:text-[#FAC42F] transition-colors flex items-center gap-3">
                  <img src={imgImage18} alt="" className="w-6 h-6" />
                  Our Blogs
                </a>
              </li>
            </ul>
          </div>

          {/* Our Menu */}
          <div>
            <h3 className="text-white font-extrabold text-4xl mb-6">Our Menu</h3>
            <div className="h-0.5 w-14 bg-[#FAC42F] mb-8"></div>
            <ul className="space-y-4">
              <li>
                <a href="#menu" className="text-white/90 text-2xl font-light hover:text-[#FAC42F] transition-colors flex items-center gap-3">
                  <img src={imgImage18} alt="" className="w-6 h-6" />
                  Burger
                </a>
              </li>
              <li>
                <a href="#menu" className="text-white/90 text-2xl font-light hover:text-[#FAC42F] transition-colors flex items-center gap-3">
                  <img src={imgImage18} alt="" className="w-6 h-6" />
                  Menu Burger
                </a>
              </li>
              <li>
                <a href="#menu" className="text-white/90 text-2xl font-light hover:text-[#FAC42F] transition-colors flex items-center gap-3">
                  <img src={imgImage18} alt="" className="w-6 h-6" />
                  Sides
                </a>
              </li>
            </ul>
          </div>

          {/* Contact Us */}
          <div>
            <h3 className="text-white font-extrabold text-4xl mb-6">Contact Us</h3>
            <div className="h-0.5 w-14 bg-[#FAC42F] mb-8"></div>
            <div className="space-y-4">
              <div>
                <p className="text-white/40 text-2xl font-medium mb-1">Monday - Friday:</p>
                <p className="text-[#FAC42F] text-xl font-medium">8am - 4 pm</p>
              </div>
              <div>
                <p className="text-white/40 text-2xl font-medium mb-1">Saturday:</p>
                <p className="text-[#FAC42F] text-xl font-medium">8am - 12 pm</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Bottom Bar */}
      <div className="bg-[#F30C0C] py-8">
        <div className="container mx-auto px-4">
          <div className="flex flex-col md:flex-row items-center justify-between gap-4">
            <div className="flex items-center gap-4">
              <img src={imgImage23} alt="" className="w-11 h-11" />
              <p className="text-white text-2xl font-medium">
                All Copyright 2025 by Brazil Burger
              </p>
            </div>

            <div className="flex items-center gap-6">
              <div className="border border-white rounded-lg px-6 py-3">
                <p className="text-white text-2xl font-medium">Terms & Conditions</p>
              </div>
              <div className="border border-white rounded-lg px-6 py-3">
                <p className="text-white text-2xl font-medium">Privacy Policy</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}
