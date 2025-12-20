using BrazilBurgerWebClient.Models.Abstract;
using BrazilBurgerWebClient.Models.Commandes;

namespace BrazilBurgerWebClient.Models
{
    public class Client : User
    {
        public ICollection<Commande> Commandes { get; set; }
            = new List<Commande>();
    }
}
