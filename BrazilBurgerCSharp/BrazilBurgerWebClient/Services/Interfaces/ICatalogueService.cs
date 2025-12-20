using BrazilBurgerWebClient.Models;

namespace BrazilBurgerWebClient.Services.Interfaces
{
    public interface ICatalogueService
    {
        List<Produit> GetCatalogue();
        List<Produit> GetProduitsParType(TypeProduit type);
        Produit? GetProduitById(int id);
    }
}
