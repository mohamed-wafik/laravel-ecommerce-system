import { useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import SingleProductSkeleton from "../../components/Skeleton/SingleProductSkeleton";
import { productAPI } from "../../api/modules/Guest/product.api";
import Breadcrumb from "../../components/ui/Breadcrumb";
import ProductImagePanel from "./ProductImagePanel";
import ProductInfoPanel from "./ProductInfoPanel";
import SliderProducts from "../../components/SliderProduct";
import ReviewsTab from "./Reviews";

const SingleProduct = () => {
  const { slug } = useParams<{ slug: string }>();
  const navigate = useNavigate();

  useEffect(() => {
    if (!slug) {
      navigate("/", { replace: true });
    }
  }, [slug, navigate]);

  const { data, isLoading } = productAPI.getDetailsProduct(slug ?? "");

  if (isLoading) {
    return <SingleProductSkeleton />;
  }

  console.log("Product data:", data); // Log the entire data object for debugging

  if (!data?.data?.product) {
    return (
      <section className="flex min-h-[60vh] items-center justify-center bg-gray-50 px-4">
        <div className="text-center">
          <h1 className="text-2xl font-bold text-gray-900">
            Product not found
          </h1>

          <button
            type="button"
            onClick={() => navigate("/shop")}
            className="mt-5 rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white transition hover:opacity-90"
          >
            Back to Shop
          </button>
        </div>
      </section>
    );
  }

  const product = data.data.product;
  const relatedProducts = data.data.related ?? [];
  const reviews = product.reviews ?? [];

  return (
    <section className="min-h-screen bg-gray-50 py-6 sm:py-8 lg:py-10">
      <div className="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <Breadcrumb
          items={[
            { label: "Home", href: "/" },
            {
              label: product.category?.name ?? "Category",
              href: `/shop?category_id=${product.category_id}`,
            },
            { label: product.name },
          ]}
        />

        {/* Main product */}
        <div className="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
          <div className="grid grid-cols-1 lg:grid-cols-2">
            <ProductImagePanel
              images={product.images}
              title={product.name}
              comparePrice={product.compare_price}
              price={product.price}
            />

            <ProductInfoPanel product={product} />
          </div>
        </div>

        {relatedProducts.length > 0 && (
          <div className="mt-10 sm:mt-12 lg:mt-14">
            <div className="mb-5 flex items-center gap-3 sm:mb-6">
              <div className="h-6 w-1 rounded-full bg-primary" />

              <h2 className="text-lg font-bold text-gray-900 sm:text-xl">
                Related Products
              </h2>
            </div>

            <SliderProducts Count={4} products={relatedProducts} />
          </div>
        )}

        {/* Reviews / product information */}
        <div className="mt-8 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm sm:mt-10 lg:mt-14">
          <ReviewsTab
            productId={product.id}
            reviews={reviews}
            isLoading={isLoading}
          />
        </div>

        {/* Related */}
      </div>
    </section>
  );
};

export default SingleProduct;
