using BrazilBurgerWebClient.Models;

namespace BrazilBurgerWebClient.Models
{
    public class Produit
    {
        public int Id { get; set; }

        public required string Nom { get; set; }

        public decimal Prix { get; set; }

        public required string ImageUrl { get; set; }

        public TypeProduit TypeProduit { get; set; }

        public bool EstArchive { get; set; }
    }
}
