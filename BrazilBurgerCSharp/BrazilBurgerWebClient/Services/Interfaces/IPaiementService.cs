using BrazilBurgerWebClient.Models.Commandes;
using BrazilBurgerWebClient.Models.Enums;

namespace BrazilBurgerWebClient.Services.Interfaces
{
    public interface IPaiementService
    {
        Paiement PayerCommande(int commandeId, ModePaiement modePaiement);
    }
}
