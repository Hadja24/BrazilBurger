using BrazilBurgerWebClient.Models;

namespace BrazilBurgerWebClient.Models.Commandes
{
    public class CommandeLigne
    {
        public int Id { get; set; }

        public int ProduitId { get; set; }
        public Produit Produit { get; set; } = null!;

        public int Quantite { get; set; }
        public decimal PrixLigne { get; set; }

        public int CommandeId { get; set; }
        public Commande? Commande { get; set; }
    }
}
