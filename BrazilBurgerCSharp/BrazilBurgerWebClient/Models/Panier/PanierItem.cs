namespace BrazilBurgerWebClient.Models.Panier
{
    public class PanierItem
    {
        public int ProduitId { get; set; }
        public required string Nom { get; set; }
        public decimal Prix { get; set; }
        public int Quantite { get; set; }

        public decimal Total => Prix * Quantite;
    }
}
