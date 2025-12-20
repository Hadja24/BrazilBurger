using BrazilBurgerWebClient.Data;
using BrazilBurgerWebClient.Models.Commandes;
using BrazilBurgerWebClient.Models;
using BrazilBurgerWebClient.Models.Panier;
using BrazilBurgerWebClient.Services.Interfaces;

namespace BrazilBurgerWebClient.Services
{
    public class CommandeService : ICommandeService
    {
        private readonly BrazilBurgerContext _context;

        public CommandeService(BrazilBurgerContext context)
        {
            _context = context;
        }

        public Commande CreerCommande(int clientId, Panier panier, TypeCommande typeCommande)
        {
            var commande = new Commande
            {
                ClientId = clientId,
                DateCommande = DateTime.Now,
                TypeCommande = typeCommande,
                Etat = EtatCommande.EN_COURS,
                TotalCommande = panier.Total
            };

            foreach (var item in panier.Items)
            {
                commande.Lignes.Add(new CommandeLigne
                {
                    ProduitId = item.ProduitId,
                    Quantite = item.Quantite,
                    PrixLigne = item.Prix * item.Quantite
                });
            }

            _context.Commandes.Add(commande);
            _context.SaveChanges();

            return commande;
        }
    }
}

