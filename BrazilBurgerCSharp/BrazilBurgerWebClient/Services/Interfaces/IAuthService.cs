using BrazilBurgerWebClient.Models;

namespace BrazilBurgerWebClient.Services.Interfaces
{
    public interface IAuthService
    {
        Client? Login(string email, string motDePasse);
        Client Register(Client client);
    }
}
