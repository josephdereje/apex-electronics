from django import forms
from catalog.models import Inquiry, Product


class StyledModelForm(forms.ModelForm):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        for name, field in self.fields.items():
            if isinstance(field.widget, forms.CheckboxInput):
                field.widget.attrs.setdefault("class", "check-input")
            elif isinstance(field.widget, forms.FileInput):
                field.widget.attrs.setdefault("class", "file-input")
            elif isinstance(field.widget, forms.Textarea):
                field.widget.attrs.setdefault("class", "field-input")
                field.widget.attrs.setdefault("rows", 5)
            else:
                field.widget.attrs.setdefault("class", "field-input")


class ProductForm(StyledModelForm):
    class Meta:
        model = Product
        fields = [
            "category",
            "name",
            "sku",
            "short_description",
            "description",
            "specifications",
            "moq",
            "lead_time",
            "image",
            "is_featured",
            "is_active",
        ]


class InquiryStatusForm(forms.ModelForm):
    class Meta:
        model = Inquiry
        fields = ["status"]
        widgets = {"status": forms.Select(attrs={"class": "field-input"})}
