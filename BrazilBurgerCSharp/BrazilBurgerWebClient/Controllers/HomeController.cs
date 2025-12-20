using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using BrazilBurgerWebClient.Models;
using BrazilBurgerWebClient.Services.Interfaces;

namespace BrazilBurgerWebClient.Controllers;

public class HomeController : Controller
{
    private readonly ILogger<HomeController> _logger;
    private readonly ICatalogueService _catalogueService;

    public HomeController(ILogger<HomeController> logger, ICatalogueService catalogueService)
    {
        _logger = logger;
        _catalogueService = catalogueService;
    }

    public IActionResult Index()
    {
        return View();
    }

    public IActionResult Accueil()
    {
        var produits = _catalogueService.GetCatalogue();
        return View("Accueil", produits);
    }

    public IActionResult Privacy()
    {
        return View();
    }

    [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
    public IActionResult Error()
    {
        return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
    }
}
