import imgImage10 from "figma:asset/5599b373d6ebb35d337cbacd3c3879a1113f5be9.png";
import imgDesignSansTitre11 from "figma:asset/ec36e05b1cd9dc03c48deeaf15dcd49218bd9e4c.png";
import imgImage9 from "figma:asset/a02e9f37f0bbd918258ede9bb6596913c8dc6dd9.png";

export function HeroSection() {
  return (
    <section className="relative h-[600px] md:h-[800px] overflow-hidden">
      {/* Background Image */}
      <div className="absolute inset-0">
        <img 
          src={imgImage10} 
          alt="Background" 
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-black/45"></div>
      </div>

      {/* Content */}
      <div className="relative container mx-auto px-4 h-full">
        <div className="grid md:grid-cols-2 gap-8 items-center h-full">
          {/* Left Content */}
          <div className="text-white pt-20 md:pt-0">
            <p className="text-[#FAC42F] font-extrabold text-2xl md:text-3xl mb-4">
              WELCOME BRAZIL BURGER
            </p>
            <h1 className="text-5xl md:text-7xl lg:text-8xl font-extrabold leading-tight mb-8">
              NEW BRAZIL<br />
              BURGER SALAD
            </h1>
            <button className="bg-[#E81717] text-white px-8 py-4 flex items-center gap-3 hover:bg-[#c91414] transition-colors text-xl">
              <span>ORDER NOW</span>
              <img src={imgImage9} alt="" className="w-7 h-7" />
            </button>
          </div>

          {/* Right Content - Burger Image */}
          <div className="hidden md:flex justify-end items-center">
            <img 
              src={imgDesignSansTitre11} 
              alt="Brazil Burger" 
              className="w-full max-w-2xl object-contain drop-shadow-2xl"
            />
          </div>
        </div>
      </div>
    </section>
  );
}
