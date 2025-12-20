using System.Collections.Generic;
using System.Linq;

namespace BrazilBurgerWebClient.Models.Panier
{
    public class Panier
    {
        public List<PanierItem> Items { get; set; } = new();

        public decimal Total => Items.Sum(i => i.Total);

        public void Ajouter(PanierItem item)
        {
            var existant = Items.FirstOrDefault(i => i.ProduitId == item.ProduitId);

            if (existant != null)
                existant.Quantite += item.Quantite;
            else
                Items.Add(item);
        }

        public void Supprimer(int produitId)
        {
            Items.RemoveAll(i => i.ProduitId == produitId);
        }

        public void Vider()
        {
            Items.Clear();
        }
    }
}
