import svgPaths from "../imports/svg-lmnj2a9teg";
import imgEllipse1 from "figma:asset/86a943c9ad8c6500d4028461fa9c0bac1db9f947.png";
import imgEllipse2 from "figma:asset/a05eb6ef7c41932aa96c71898320b90e04dc05c1.png";
import imgEllipse3 from "figma:asset/594e4730b3ef7ed4aa67109d5ba7a116201389a7.png";

interface BurgerCardProps {
  image: string;
  name: string;
  description: string;
  price: string;
}

function BurgerCard({ image, name, description, price }: BurgerCardProps) {
  return (
    <div className="flex flex-col items-center">
      {/* Image Container with Dashed Border */}
      <div className="relative mb-6">
        <div className="relative">
          <svg className="absolute inset-0 w-[293px] h-[293px] -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2" fill="none" preserveAspectRatio="none" viewBox="0 0 294 294">
            <circle cx="147" cy="147" r="146.5" stroke="#EB1E1E" strokeDasharray="5 5" />
          </svg>
          <img src={image} alt={name} className="w-[260px] h-[260px] object-cover rounded-full" />
        </div>
      </div>

      {/* Card Content */}
      <div className="bg-white rounded-3xl px-8 py-10 w-full max-w-sm text-center shadow-lg">
        <h3 className="text-black font-bold text-4xl mb-4">{name}</h3>
        <p className="text-black/70 font-light mb-6 text-base leading-relaxed">
          {description}
        </p>
        <p className="text-[#F30C0C] font-bold text-3xl">{price}</p>
      </div>
    </div>
  );
}

export function PopularItems() {
  const burgers = [
    {
      image: imgEllipse1,
      name: "Cheese Burger",
      description: "Toasted bun, 130g beef patty, double cheddar, homemade chips, and homemade sauce.",
      price: "3 500 F CFA"
    },
    {
      image: imgEllipse2,
      name: "Bum Burger",
      description: "Toasted bun, beef patty, Brazilian salad, breaded mozzarella, special mayo, and halal bacon.",
      price: "6 500 F CFA"
    },
    {
      image: imgEllipse3,
      name: "Double Burger",
      description: "Toasted bun, 130g beef patty, double cheddar, homemade chips, and homemade sauce.",
      price: "6 000 F CFA"
    }
  ];

  return (
    <section className="py-20 bg-[#F4F1EA]">
      <div className="container mx-auto px-4">
        {/* Section Title */}
        <div className="text-center mb-16">
          <div className="flex items-center justify-center gap-4 mb-6">
            <svg className="w-12 h-12" fill="none" preserveAspectRatio="none" viewBox="0 0 52 52">
              <path d={svgPaths.p8f90e00} fill="#EB1E1E" />
            </svg>
            <h2 className="text-6xl md:text-7xl font-extrabold text-[#FAC42F]">BEST FOOD</h2>
            <svg className="w-12 h-12" fill="none" preserveAspectRatio="none" viewBox="0 0 52 52">
              <path d={svgPaths.p8f90e00} fill="#EB1E1E" />
            </svg>
          </div>
          <h3 className="text-5xl md:text-7xl font-extrabold text-black">
            Popular Food Items
          </h3>
        </div>

        {/* Burger Cards Grid */}
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-12 max-w-7xl mx-auto">
          {burgers.map((burger, index) => (
            <BurgerCard key={index} {...burger} />
          ))}
        </div>
      </div>
    </section>
  );
}
