using BrazilBurgerWebClient.Data;
using BrazilBurgerWebClient.Models.Commandes;
using BrazilBurgerWebClient.Models.Enums;
using BrazilBurgerWebClient.Services.Interfaces;
using Microsoft.EntityFrameworkCore;

namespace BrazilBurgerWebClient.Services
{
    public class PaiementService : IPaiementService
    {
        private readonly BrazilBurgerContext _context;

        public PaiementService(BrazilBurgerContext context)
        {
            _context = context;
        }

        public Paiement PayerCommande(int commandeId, ModePaiement modePaiement)
        {
            var commande = _context.Commandes
                .Include(c => c.Lignes)
                .FirstOrDefault(c => c.Id == commandeId);

            if (commande == null)
                throw new Exception("Commande introuvable");

            bool dejaPayee = _context.Paiements
                .Any(p => p.CommandeId == commandeId);

            if (dejaPayee)
                throw new Exception("Cette commande est déjà payée");

            var paiement = new Paiement
            {
                CommandeId = commande.Id,
                DatePaiement = DateTime.Now,
                Montant = commande.TotalCommande,
                ModePaiement = modePaiement
            };

            _context.Paiements.Add(paiement);
            _context.SaveChanges();

            return paiement;
        }
    }
}
