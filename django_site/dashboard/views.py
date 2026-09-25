from django.contrib import messages
from django.contrib.auth import authenticate, login, logout
from django.contrib.auth.decorators import login_required
from django.shortcuts import get_object_or_404, redirect, render
from django.db.models import Count

from catalog.models import Category, Inquiry, Product
from .forms import InquiryStatusForm, ProductForm


def admin_login(request):
    if request.user.is_authenticated:
        return redirect("dashboard_home")
    error = ""
    if request.method == "POST":
        user = authenticate(
            request,
            username=request.POST.get("username", ""),
            password=request.POST.get("password", ""),
        )
        if user is not None:
            login(request, user)
            return redirect("dashboard_home")
        error = "Invalid username or password."
    return render(request, "dashboard/login.html", {"error": error})


def admin_logout(request):
    logout(request)
    return redirect("admin_login")


@login_required
def dashboard_home(request):
    return render(
        request,
        "dashboard/home.html",
        {
            "product_count": Product.objects.count(),
            "active_count": Product.objects.filter(is_active=True).count(),
            "inquiry_count": Inquiry.objects.count(),
            "new_inquiries": Inquiry.objects.filter(status="new").count(),
            "recent_products": Product.objects.select_related("category")[:6],
            "recent_inquiries": Inquiry.objects.all()[:5],
            "category_counts": Category.objects.annotate(total=Count("products")),
        },
    )


@login_required
def product_list(request):
    category_slug = request.GET.get("category")
    items = Product.objects.select_related("category")
    if category_slug:
        items = items.filter(category__slug=category_slug)
    return render(
        request,
        "dashboard/products.html",
        {
            "products": items,
            "categories": Category.objects.all(),
            "active_category": category_slug,
        },
    )


@login_required
def product_create(request):
    form = ProductForm(request.POST or None, request.FILES or None)
    if request.method == "POST" and form.is_valid():
        form.save()
        messages.success(request, "Product uploaded and saved to the catalog.")
        return redirect("dashboard_products")
    return render(request, "dashboard/product_form.html", {"form": form, "title": "Upload product"})


@login_required
def product_edit(request, pk):
    product = get_object_or_404(Product, pk=pk)
    form = ProductForm(request.POST or None, request.FILES or None, instance=product)
    if request.method == "POST" and form.is_valid():
        form.save()
        messages.success(request, "Product updated.")
        return redirect("dashboard_products")
    return render(
        request,
        "dashboard/product_form.html",
        {"form": form, "title": "Edit product", "product": product},
    )


@login_required
def product_delete(request, pk):
    product = get_object_or_404(Product, pk=pk)
    if request.method == "POST":
        product.delete()
        messages.success(request, "Product removed from the catalog.")
        return redirect("dashboard_products")
    return render(request, "dashboard/product_delete.html", {"product": product})


@login_required
def inquiry_list(request):
    if request.method == "POST":
        inquiry = get_object_or_404(Inquiry, pk=request.POST.get("inquiry_id"))
        form = InquiryStatusForm(request.POST, instance=inquiry)
        if form.is_valid():
            form.save()
            messages.success(request, "Inquiry status updated.")
        return redirect("dashboard_inquiries")
    return render(
        request,
        "dashboard/inquiries.html",
        {"inquiries": Inquiry.objects.all(), "status_choices": Inquiry.STATUS_CHOICES},
    )
