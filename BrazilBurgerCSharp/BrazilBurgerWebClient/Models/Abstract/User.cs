namespace BrazilBurgerWebClient.Models.Abstract
{
    public abstract class User
    {
        public int Id { get; set; }

        public string Nom { get; set; } = null!;
        public string Prenom { get; set; } = null!;
        public string Telephone { get; set; } = null!;
        public string Email { get; set; } = null!;
        public string MotDePasse { get; set; } = null!;
    }
}
