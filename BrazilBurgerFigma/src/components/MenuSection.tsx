import { useState } from 'react';
import svgPaths from "../imports/svg-lmnj2a9teg";
import imgImage11 from "figma:asset/237be7b57ec8bc560aef60483ada415b4655edb4.png";
import imgImage12 from "figma:asset/41582263fc00f0274ef0fb98b6e72372738b4a1a.png";
import imgImage13 from "figma:asset/f4d48db378bc8b646cb762fa9a4e079c55641014.png";
import imgEllipse4 from "figma:asset/85ca0d60ff43e0b0ccec13226c4fa0f217f12b95.png";
import imgEllipse5 from "figma:asset/382a4b4d7ca300bd9a17b5222b62a17967af17a9.png";
import imgEllipse6 from "figma:asset/0f8b982f800241f547e0149be892166d9538d000.png";
import imgEllipse7 from "figma:asset/8e666f582c91ce6e007d8ff220cdf6d59a927f87.png";
import imgEllipse8 from "figma:asset/8ea40168fef48da15285e5f2d21e419db82ea9a3.png";
import imgEllipse9 from "figma:asset/ebc49ade9f7ec6d53bd198957a39734ab3fe92a6.png";
import imgEllipse10 from "figma:asset/dca6594c06d40ac04df3c8b2aa31d3203e155118.png";
import imgEllipse11 from "figma:asset/3b069cfaf73729fb62eeb39da8767c7ec71886ad.png";

interface MenuItem {
  image: string;
  name: string;
  description: string;
  price: string;
}

function MenuItemCard({ image, name, description, price }: MenuItem) {
  return (
    <div className="flex items-center gap-6 py-8 border-b border-black/10 last:border-b-0">
      <img 
        src={image} 
        alt={name} 
        className="w-28 h-28 object-cover rounded-full flex-shrink-0"
      />
      <div className="flex-1">
        <h4 className="text-[#F30C0C] font-extrabold text-xl tracking-tight">
          {name}
        </h4>
        <p className="text-black/70 font-light text-xs mt-2 leading-relaxed">
          {description}
        </p>
      </div>
      <p className="text-black font-medium text-xl flex-shrink-0">
        {price}
      </p>
    </div>
  );
}

export function MenuSection() {
  const [activeTab, setActiveTab] = useState('fastfood');

  const menuItems: MenuItem[] = [
    {
      image: imgEllipse4,
      name: "BURGER SALAD",
      description: "Toasted bun, beef patty, Brazilian salad, breaded mozzarella, special mayo, and halal bacon.",
      price: "8 000 F CFA"
    },
    {
      image: imgEllipse11,
      name: "CHEESE BURGER",
      description: "Toasted bun, beef patty, Brazilian salad, breaded mozzarella, special mayo, and halal bacon.",
      price: "8 000 F CFA"
    },
    {
      image: imgEllipse5,
      name: "BUM BURGER",
      description: "Toasted bun, beef patty, Brazilian salad, breaded mozzarella, special mayo, and halal bacon.",
      price: "8 000 F CFA"
    },
    {
      image: imgEllipse8,
      name: "DOUBLE BURGER",
      description: "Toasted bun, beef patty, Brazilian salad, breaded mozzarella, special mayo, and halal bacon.",
      price: "8 000 F CFA"
    },
    {
      image: imgEllipse6,
      name: "BURGER RETRO",
      description: "Toasted bun, beef patty, Brazilian salad, breaded mozzarella, special mayo, and halal bacon.",
      price: "8 000 F CFA"
    },
    {
      image: imgEllipse9,
      name: "BURGER RANCH",
      description: "Toasted bun, beef patty, Brazilian salad, breaded mozzarella, special mayo, and halal bacon.",
      price: "8 000 F CFA"
    },
    {
      image: imgEllipse7,
      name: "BURGER BBQ",
      description: "Toasted bun, beef patty, Brazilian salad, breaded mozzarella, special mayo, and halal bacon.",
      price: "8 000 F CFA"
    },
    {
      image: imgEllipse10,
      name: "DOUBLE SMASH",
      description: "Toasted bun, beef patty, Brazilian salad, breaded mozzarella, special mayo, and halal bacon.",
      price: "8 000 F CFA"
    }
  ];

  return (
    <section className="py-20 bg-white relative">
      <div className="absolute inset-0 bg-[#F4F1EA]"></div>
      
      <div className="relative container mx-auto px-4">
        {/* Section Title */}
        <div className="text-center mb-12">
          <div className="flex items-center justify-center gap-4 mb-6">
            <svg className="w-12 h-12" fill="none" preserveAspectRatio="none" viewBox="0 0 52 52">
              <path d={svgPaths.p8f90e00} fill="#EB1E1E" />
            </svg>
            <h2 className="text-5xl md:text-6xl font-extrabold text-[#FAC42F]">FOOD MENU</h2>
            <svg className="w-12 h-12" fill="none" preserveAspectRatio="none" viewBox="0 0 52 52">
              <path d={svgPaths.p8f90e00} fill="#EB1E1E" />
            </svg>
          </div>
          <h3 className="text-5xl md:text-7xl font-extrabold text-black">
            Brazil Food Menu
          </h3>
        </div>

        {/* Tabs */}
        <div className="flex items-center justify-center gap-8 mb-12 flex-wrap">
          <button
            onClick={() => setActiveTab('fastfood')}
            className={`flex items-center gap-3 px-4 py-2 rounded-lg transition-all ${
              activeTab === 'fastfood' 
                ? 'text-[#F30C0C]' 
                : 'text-black hover:text-[#F30C0C]'
            }`}
          >
            <img src={imgImage12} alt="" className="w-10 h-10" />
            <span className="font-semibold text-xl">Fast Food</span>
          </button>

          <div className="h-16 w-px bg-black/20 hidden md:block"></div>

          <button
            onClick={() => setActiveTab('burger')}
            className={`flex items-center gap-3 px-4 py-2 rounded-lg transition-all ${
              activeTab === 'burger' 
                ? 'text-[#F30C0C]' 
                : 'text-black hover:text-[#F30C0C]'
            }`}
          >
            <img src={imgImage13} alt="" className="w-7 h-7" />
            <span className="font-medium text-xl">Burger</span>
          </button>

          <div className="h-16 w-px bg-black/20 hidden md:block"></div>

          <button
            onClick={() => setActiveTab('fries')}
            className={`flex items-center gap-3 px-4 py-2 rounded-lg transition-all ${
              activeTab === 'fries' 
                ? 'text-[#F30C0C]' 
                : 'text-black hover:text-[#F30C0C]'
            }`}
          >
            <img src={imgImage11} alt="" className="w-8 h-8" />
            <span className="font-medium text-xl">Fries & Drink</span>
          </button>
        </div>

        {/* Menu Items */}
        <div className="max-w-5xl mx-auto bg-white rounded-3xl shadow-xl p-8 md:p-12">
          <div className="grid md:grid-cols-2 gap-x-12">
            {menuItems.map((item, index) => (
              <MenuItemCard key={index} {...item} />
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}
