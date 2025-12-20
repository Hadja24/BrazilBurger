using BrazilBurgerWebClient.Data;
using BrazilBurgerWebClient.Models;
using BrazilBurgerWebClient.Services.Interfaces;
using Microsoft.EntityFrameworkCore;

namespace BrazilBurgerWebClient.Services
{
    public class CatalogueService : ICatalogueService
    {
        private readonly BrazilBurgerContext _context;

        public CatalogueService(BrazilBurgerContext context)
        {
            _context = context;
        }

        public List<Produit> GetCatalogue()
        {
            return _context.Produits
                .Where(p => !p.EstArchive)
                .ToList();
        }

        public List<Produit> GetProduitsParType(TypeProduit type)
        {
            return _context.Produits
                .Where(p => p.TypeProduit == type && !p.EstArchive)
                .ToList();
        }

        public Produit? GetProduitById(int id)
        {
            return _context.Produits
                .FirstOrDefault(p => p.Id == id && !p.EstArchive);
        }
    }
}
