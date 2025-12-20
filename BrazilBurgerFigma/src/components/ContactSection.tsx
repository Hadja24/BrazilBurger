import imgImage14 from "figma:asset/931dbb29943312ece756849f13b92f471cc4b626.png";
import imgImage15 from "figma:asset/384a862c3d217678c2a84b507cfb6393ec695b00.png";

export function ContactSection() {
  return (
    <section className="py-16 bg-[#F4F1EA]">
      <div className="container mx-auto px-4">
        <div className="bg-[#DDAA1F] rounded-[30px] p-8 md:p-12 max-w-6xl mx-auto">
          <div className="grid md:grid-cols-2 gap-8 items-center">
            {/* Address */}
            <div className="flex items-center gap-6">
              <img src={imgImage14} alt="Location" className="w-16 h-16 flex-shrink-0" />
              <div className="text-white">
                <p className="font-semibold text-lg mb-1">Address</p>
                <p className="font-extrabold text-2xl">
                  Villa 5b, Avenue. Birago Diop, Dakar
                </p>
              </div>
            </div>

            {/* Phone */}
            <div className="flex items-center gap-6">
              <img src={imgImage15} alt="Phone" className="w-14 h-14 flex-shrink-0" />
              <div className="text-white">
                <p className="font-semibold text-lg mb-1">Call</p>
                <p className="font-extrabold text-2xl">
                  +221 77 737 01 01
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
