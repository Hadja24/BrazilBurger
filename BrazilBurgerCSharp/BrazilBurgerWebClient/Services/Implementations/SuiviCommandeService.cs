using BrazilBurgerWebClient.Data;
using BrazilBurgerWebClient.Models.Commandes;
using BrazilBurgerWebClient.Services.Interfaces;
using Microsoft.EntityFrameworkCore;

namespace BrazilBurgerWebClient.Services
{
    public class SuiviCommandeService : ISuiviCommandeService
    {
        private readonly BrazilBurgerContext _context;

        public SuiviCommandeService(BrazilBurgerContext context)
        {
            _context = context;
        }

        public List<Commande> GetCommandesClient(int clientId)
        {
            return _context.Commandes
                .Where(c => c.ClientId == clientId)
                .OrderByDescending(c => c.DateCommande)
                .ToList();
        }

        public Commande? GetDetailsCommande(int commandeId, int clientId)
        {
            return _context.Commandes
                .Include(c => c.Lignes)
                .Include(c => c.Paiement)
                .FirstOrDefault(c => c.Id == commandeId && c.ClientId == clientId);
        }
    }
}

