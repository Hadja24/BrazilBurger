using System.Collections.Generic;

namespace BrazilBurgerWebClient.Models.Livraisons
{
    public class Zone
    {
        public int Id { get; set; }
        public required string Nom { get; set; }
        public decimal PrixLivraison { get; set; }

        public ICollection<Quartier> Quartiers { get; set; }

        public Zone()
        {
            Quartiers = new List<Quartier>();
        }
    }
}
