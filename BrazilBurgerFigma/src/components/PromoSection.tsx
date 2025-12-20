import imgRectangle10 from "figma:asset/b216779f89fc63e1e117b618de3f07a1ac98d18c.png";
import imgNatureMorteDeDelicieuxHamburgerAmericainRemovebgPreview1 from "figma:asset/4af08568fd2d8d143d513d6094205e124714b9b1.png";
import imgBurgerDeViandeSurPlancheDeBoisFritesVueLateraleRemovebgPreview2 from "figma:asset/0c95ec39d46b6184a3e995f1bbeba81613a82fb0.png";
import imgVueDUnDelicieuxBurgerAvecFromageEtViandeRemovebgPreview1 from "figma:asset/b6eba476055cf15740336b6a531d846d8dcc4746.png";
import imgImage9 from "figma:asset/a02e9f37f0bbd918258ede9bb6596913c8dc6dd9.png";

interface PromoCardProps {
  image: string;
  title: string;
}

function PromoCard({ image, title }: PromoCardProps) {
  return (
    <div className="relative group overflow-hidden rounded-lg h-[270px]">
      {/* Background */}
      <img src={imgRectangle10} alt="" className="absolute inset-0 w-full h-full object-cover" />
      
      {/* Burger Image */}
      <div className="absolute inset-0 flex items-center justify-center">
        <img 
          src={image} 
          alt={title} 
          className="h-[200px] object-contain drop-shadow-2xl transition-transform group-hover:scale-110 duration-300"
        />
      </div>

      {/* Content Overlay */}
      <div className="absolute inset-0 flex flex-col justify-between p-6">
        <div>
          <p className="text-[#F30C0C] font-extrabold text-sm tracking-tight">
            ON THIS WEEK
          </p>
          <h3 className="text-white font-black text-3xl md:text-4xl mt-2 drop-shadow-lg">
            {title}
          </h3>
          <p className="text-[#FAC42F] font-extrabold text-sm mt-2">
            Limits Time Offer
          </p>
        </div>

        <button className="bg-[#E81717] text-white px-6 py-3 flex items-center gap-2 hover:bg-[#c91414] transition-colors w-fit text-sm">
          <span>ORDER NOW</span>
          <img src={imgImage9} alt="" className="w-5 h-5" />
        </button>
      </div>
    </div>
  );
}

export function PromoSection() {
  const promos = [
    {
      image: imgNatureMorteDeDelicieuxHamburgerAmericainRemovebgPreview1,
      title: "BURGER RANCH"
    },
    {
      image: imgBurgerDeViandeSurPlancheDeBoisFritesVueLateraleRemovebgPreview2,
      title: "BURGER RETRO"
    },
    {
      image: imgVueDUnDelicieuxBurgerAvecFromageEtViandeRemovebgPreview1,
      title: "BURGER BBQ"
    }
  ];

  return (
    <section className="py-16 bg-[#F4F1EA]">
      <div className="container mx-auto px-4">
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
          {promos.map((promo, index) => (
            <PromoCard key={index} {...promo} />
          ))}
        </div>
      </div>
    </section>
  );
}
