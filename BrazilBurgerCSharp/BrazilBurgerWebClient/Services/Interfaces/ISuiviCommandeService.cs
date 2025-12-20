using BrazilBurgerWebClient.Models.Commandes;

namespace BrazilBurgerWebClient.Services.Interfaces
{
    public interface ISuiviCommandeService
    {
        List<Commande> GetCommandesClient(int clientId);
        Commande? GetDetailsCommande(int commandeId, int clientId);
    }
}
