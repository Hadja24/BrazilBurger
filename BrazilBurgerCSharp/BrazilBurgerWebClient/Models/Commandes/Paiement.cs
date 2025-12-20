using BrazilBurgerWebClient.Models.Enums;

namespace BrazilBurgerWebClient.Models.Commandes
{
    public class Paiement
    {
        public int Id { get; set; }

        public DateTime DatePaiement { get; set; }

        public decimal Montant { get; set; }

        public ModePaiement ModePaiement { get; set; }

        public int CommandeId { get; set; }
        public Commande Commande { get; set; } = null!;
    }
}
