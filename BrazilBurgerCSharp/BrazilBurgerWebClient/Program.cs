using BrazilBurgerWebClient.Data;
using BrazilBurgerWebClient.Services.Interfaces;
using BrazilBurgerWebClient.Services;
using Microsoft.EntityFrameworkCore;
using Npgsql.EntityFrameworkCore.PostgreSQL;
using BrazilBurgerWebClient.Models;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
builder.Services.AddControllersWithViews();
builder.Services.AddDbContext<BrazilBurgerContext>(options =>
    options.UseNpgsql(builder.Configuration.GetConnectionString("DefaultConnection")));
builder.Services.AddScoped<IAuthService, AuthService>();
builder.Services.AddScoped<ICatalogueService, CatalogueService>();
builder.Services.AddScoped<ICommandeService, CommandeService>();
builder.Services.AddScoped<IPaiementService, PaiementService>();
builder.Services.AddScoped<ISuiviCommandeService, SuiviCommandeService>();
builder.Services.AddSession();

var app = builder.Build();

// Seed the database
using (var scope = app.Services.CreateScope())
{
    var context = scope.ServiceProvider.GetRequiredService<BrazilBurgerContext>();
    context.Database.Migrate(); // Ensure migrations are applied

    if (!context.Produits.Any())
    {
        context.Produits.AddRange(
            new Produit { Nom = "Burger Classique", Prix = 5000, ImageUrl = "~/images/burger1.jpg", TypeProduit = TypeProduit.BURGER, EstArchive = false },
            new Produit { Nom = "Burger Cheese", Prix = 6000, ImageUrl = "~/images/burger2.jpg", TypeProduit = TypeProduit.BURGER, EstArchive = false },
            new Produit { Nom = "Frites", Prix = 2000, ImageUrl = "~/images/frites.jpg", TypeProduit = TypeProduit.EXTRA, EstArchive = false },
            new Produit { Nom = "Coca-Cola", Prix = 1500, ImageUrl = "~/images/coca.jpg", TypeProduit = TypeProduit.EXTRA, EstArchive = false }
        );
        context.SaveChanges();
    }
}

// Configure the HTTP request pipeline.
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Home/Error");
    // The default HSTS value is 30 days. You may want to change this for production scenarios, see https://aka.ms/aspnetcore-hsts.
    app.UseHsts();
}

app.UseHttpsRedirection();
app.UseStaticFiles();

app.UseRouting();

app.UseAuthorization();

app.UseSession();
app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}");

app.Run();
