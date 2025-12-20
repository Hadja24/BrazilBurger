using BrazilBurgerWebClient.Data;
using BrazilBurgerWebClient.Models;
using BrazilBurgerWebClient.Services.Interfaces;

namespace BrazilBurgerWebClient.Services
{
    public class AuthService : IAuthService
    {
        private readonly BrazilBurgerContext _context;

        public AuthService(BrazilBurgerContext context)
        {
            _context = context;
        }

        public Client? Login(string email, string motDePasse)
        {
            return _context.Clients
                .FirstOrDefault(c => c.Email == email && c.MotDePasse == motDePasse);
        }

        public Client Register(Client client)
        {
            _context.Clients.Add(client);
            _context.SaveChanges();
            return client;
        }
    }
}
