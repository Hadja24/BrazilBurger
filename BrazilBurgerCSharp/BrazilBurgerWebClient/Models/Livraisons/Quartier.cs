namespace BrazilBurgerWebClient.Models.Livraisons
{
    public class Quartier
    {
        public int Id { get; set; }
        public required string Nom { get; set; }

        public int ZoneId { get; set; }
        public Zone? Zone { get; set; }
    }
}
