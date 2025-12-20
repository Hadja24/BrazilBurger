using BrazilBurgerWebClient.Models;
using System;
using System.Collections.Generic;

namespace BrazilBurgerWebClient.Models.Commandes
{
    public class Commande
    {
        public int Id { get; set; }

        public DateTime DateCommande { get; set; }

        public TypeCommande TypeCommande { get; set; }
        public EtatCommande Etat { get; set; }

        public decimal TotalCommande { get; set; }

        public int ClientId { get; set; }
        public Client? Client { get; set; }

        public ICollection<CommandeLigne> Lignes { get; set; }
        public Paiement? Paiement { get; set; }

        public Commande()
        {
            Lignes = new List<CommandeLigne>();
        }
    }
}
