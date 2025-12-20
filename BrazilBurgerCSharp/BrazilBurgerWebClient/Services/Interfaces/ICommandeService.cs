using BrazilBurgerWebClient.Models;
using BrazilBurgerWebClient.Models.Commandes;
using BrazilBurgerWebClient.Models.Panier;

namespace BrazilBurgerWebClient.Services.Interfaces
{
    public interface ICommandeService
    {
        Commande CreerCommande(int clientId, Panier panier, TypeCommande typeCommande);
    }
}
