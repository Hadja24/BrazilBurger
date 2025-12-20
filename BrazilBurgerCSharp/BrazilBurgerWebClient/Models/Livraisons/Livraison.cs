using BrazilBurgerWebClient.Models.Commandes;
using System;

namespace BrazilBurgerWebClient.Models.Livraisons
{
    public class Livraison
    {
        public int Id { get; set; }
        public DateTime DateLivraison { get; set; }
        public required string Statut { get; set; }

        public int LivreurId { get; set; }
        public Livreur? Livreur { get; set; }

        public int CommandeId { get; set; }
        public Commande? Commande { get; set; }
    }
}
