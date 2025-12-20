using BrazilBurgerWebClient.Models.Commandes;
using BrazilBurgerWebClient.Models.Livraisons;
using BrazilBurgerWebClient.Models;
using Microsoft.EntityFrameworkCore;

namespace BrazilBurgerWebClient.Data
{
    public class BrazilBurgerContext : DbContext
    {
        public BrazilBurgerContext(DbContextOptions<BrazilBurgerContext> options)
            : base(options)
        {
        }

        // Users
        public DbSet<Client> Clients { get; set; }
        public DbSet<Gestionnaire> Gestionnaires { get; set; }
        public DbSet<Livreur> Livreurs { get; set; }

        // Commandes
        public DbSet<Commande> Commandes { get; set; }
        public DbSet<CommandeLigne> CommandeLignes { get; set; }
        public DbSet<Paiement> Paiements { get; set; }

        // Livraison
        public DbSet<Zone> Zones { get; set; }
        public DbSet<Quartier> Quartiers { get; set; }
        public DbSet<Livraison> Livraisons { get; set; }

        // Produits
        public DbSet<Produit> Produits { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);

            // Configurations clés primaires / relations

            modelBuilder.Entity<Client>()
                .HasMany(c => c.Commandes)
                .WithOne(c => c.Client)
                .HasForeignKey(c => c.ClientId);

            modelBuilder.Entity<Commande>()
                .HasMany(c => c.Lignes)
                .WithOne(l => l.Commande)
                .HasForeignKey(l => l.CommandeId);

            modelBuilder.Entity<Commande>()
                .HasOne(c => c.Paiement)
                .WithOne(p => p.Commande)
                .HasForeignKey<Paiement>(p => p.CommandeId);

            modelBuilder.Entity<Zone>()
                .HasMany(z => z.Quartiers)
                .WithOne(q => q.Zone)
                .HasForeignKey(q => q.ZoneId);

            modelBuilder.Entity<Livraison>()
                .HasOne(l => l.Commande)
                .WithOne()
                .HasForeignKey<Livraison>(l => l.CommandeId);

            modelBuilder.Entity<Livraison>()
                .HasOne(l => l.Livreur)
                .WithMany()
                .HasForeignKey(l => l.LivreurId);

            modelBuilder.Entity<CommandeLigne>()
                .HasOne(l => l.Produit)
                .WithMany()
                .HasForeignKey(l => l.ProduitId);

            // Enum stockage en string
            modelBuilder.Entity<Commande>()
                .Property(c => c.TypeCommande)
                .HasConversion<string>();

            modelBuilder.Entity<Commande>()
                .Property(c => c.Etat)
                .HasConversion<string>();

            modelBuilder.Entity<Paiement>()
                .Property(p => p.ModePaiement)
                .HasConversion<string>();
        }
    }
}
