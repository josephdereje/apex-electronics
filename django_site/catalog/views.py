from django.http import JsonResponse
from django.shortcuts import get_object_or_404, redirect, render
from django.contrib import messages

from .forms import InquiryForm
from .models import Category, Product


def home(request):
    return render(
        request,
        "catalog/home.html",
        {
            "featured_products": Product.objects.filter(is_active=True, is_featured=True)[:8],
            "categories": Category.objects.all(),
            "inquiry_form": InquiryForm(),
        },
    )


def products(request):
    category_slug = request.GET.get("category")
    items = Product.objects.filter(is_active=True).select_related("category")
    active_category = None
    if category_slug:
        active_category = get_object_or_404(Category, slug=category_slug)
        items = items.filter(category=active_category)
    return render(
        request,
        "catalog/products.html",
        {
            "products": items,
            "categories": Category.objects.all(),
            "active_category": active_category,
        },
    )


def product_detail(request, slug):
    product = get_object_or_404(Product.objects.select_related("category"), slug=slug, is_active=True)
    related = (
        Product.objects.filter(is_active=True, category=product.category)
        .exclude(pk=product.pk)[:3]
    )
    return render(
        request,
        "catalog/product_detail.html",
        {"product": product, "related": related},
    )


def oem(request):
    return render(request, "catalog/oem.html")


def about(request):
    return render(request, "catalog/about.html")


def contact(request):
    form = InquiryForm(request.POST or None)
    if request.method == "POST":
        if form.is_valid():
            form.save()
            messages.success(
                request,
                "Inquiry received. The export team replies within 24 hours on business days.",
            )
            return redirect("contact")
        messages.error(request, "Please complete the required fields and try again.")
    return render(request, "catalog/contact.html", {"form": form})


def api_products(request):
    category_slug = request.GET.get("category")
    items = Product.objects.filter(is_active=True).select_related("category")
    if category_slug:
        items = items.filter(category__slug=category_slug)
    data = [
        {
            "id": product.id,
            "name": product.name,
            "slug": product.slug,
            "sku": product.sku,
            "category": product.category.slug,
            "category_name": product.category.name,
            "short_description": product.short_description,
            "moq": product.moq,
            "lead_time": product.lead_time,
            "image": product.image.url if product.image else "",
            "featured": product.is_featured,
        }
        for product in items
    ]
    return JsonResponse({"products": data})


def quick_inquiry(request):
    if request.method == "POST":
        form = InquiryForm(request.POST)
        if form.is_valid():
            form.save()
            messages.success(request, "Message sent. The export team replies within 24 hours.")
        else:
            messages.error(request, "Please add your name, email, and message.")
    return redirect(request.META.get("HTTP_REFERER") or "home")
