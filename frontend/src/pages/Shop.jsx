import { useEffect, useMemo, useState } from "react";
import { useOutletContext } from "react-router-dom";

const API_BASE_URL = (import.meta.env.VITE_API_BASE_URL || "").replace(
  /\/+$/,
  "",
);

function formatPrice(value) {
  const amount = Number(value);
  if (Number.isNaN(amount)) return "pkr 0.00";
  return `${amount.toFixed(2)} r.s`;
}

function resolveImageUrl(product) {
  const raw = product?.image_url || product?.image;
  if (!raw || !API_BASE_URL) return "";

  if (/^https?:\/\//i.test(raw)) return raw;
  if (raw.startsWith("/")) return `${API_BASE_URL}${raw}`;

  return `${API_BASE_URL}/storage/${raw}`;
}

export default function Shop() {
  const { isDark } = useOutletContext();
  const [products, setProducts] = useState([]);
  const [activeCategory, setActiveCategory] = useState("All");
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const panelClass = isDark
    ? "rounded-2xl border border-white/10 bg-white/5"
    : "rounded-2xl border border-gray-200 bg-white";

  useEffect(() => {
    const controller = new AbortController();

    async function loadProducts() {
      try {
        setLoading(true);
        setError("");

        if (!API_BASE_URL) {
          throw new Error(
            "VITE_API_BASE_URL is missing. Set it in frontend/.env.",
          );
        }

        const response = await fetch(`${API_BASE_URL}/api/products`, {
          signal: controller.signal,
          headers: { Accept: "application/json" },
        });

        const payload = await response.json();
        if (!response.ok || !payload?.success) {
          throw new Error(payload?.message || "Failed to load products.");
        }

        const normalizedProducts = Array.isArray(payload.data)
          ? payload.data.map((item) => ({
              ...item,
              image_url: resolveImageUrl(item),
            }))
          : [];

        setProducts(normalizedProducts);
      } catch (err) {
        if (err.name !== "AbortError") {
          setError(err.message || "Failed to load products.");
        }
      } finally {
        setLoading(false);
      }
    }

    loadProducts();
    return () => controller.abort();
  }, []);

  const categories = useMemo(() => {
    const unique = [
      ...new Set(products.map((item) => item.category).filter(Boolean)),
    ];
    return ["All", ...unique];
  }, [products]);

  const filteredProducts = useMemo(() => {
    if (activeCategory === "All") return products;
    return products.filter((item) => item.category === activeCategory);
  }, [products, activeCategory]);

  return (
    <div className="mx-auto flex w-full max-w-7xl flex-col gap-6 px-6 py-10 md:py-14">
      <section className={`${panelClass} p-6`}>
        <div className="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
          <div>
            <p className="text-sm text-indigo-400">Shop Collection</p>
            <h1 className="text-3xl font-bold md:text-4xl">
              Find Your Signature Shirt
            </h1>
          </div>
          <div className="flex flex-wrap gap-2">
            {categories.map((category) => (
              <button
                key={category}
                type="button"
                onClick={() => setActiveCategory(category)}
                className={`rounded-full px-4 py-2 text-sm font-medium transition ${
                  activeCategory === category
                    ? "bg-indigo-500 text-white"
                    : "border border-current/20 hover:border-indigo-400 hover:text-indigo-400"
                }`}
              >
                {category}
              </button>
            ))}
          </div>
        </div>
      </section>

      <section className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {loading && (
          <article className={`${panelClass} p-5`}>
            <p>Loading products...</p>
          </article>
        )}

        {!loading && error && (
          <article className={`${panelClass} p-5`}>
            <p className="text-red-500">{error}</p>
          </article>
        )}

        {!loading &&
          !error &&
          filteredProducts.map((product) => (
            <article
              key={product.id}
              className={`${panelClass} overflow-hidden`}
            >
              <div className="h-98 bg-gradient-to-br from-indigo-500/35 via-cyan-500/20 to-emerald-400/20 p-4">
                {product.image_url ? (
                  <img
                    src={product.image_url}
                    alt={product.name}
                    className="h-full w-full rounded-lg object-cover"
                  />
                ) : (
                  <span className="inline-flex rounded-full bg-black/25 px-3 py-1 text-xs font-semibold text-white">
                    No Image
                  </span>
                )}
              </div>
              <div className="p-5">
                <h2 className="text-lg font-semibold">{product.name}</h2>
                <p className="mt-1 text-sm opacity-80">{product.category}</p>
                <div className="mt-3 flex items-center gap-2">
                  <p className="text-xl font-bold">
                    {formatPrice(product.discount_price || product.price)}
                  </p>
                  {product.discount_price && (
                    <p className="text-sm text-gray-400 line-through">
                      {formatPrice(product.price)}
                    </p>
                  )}
                </div>
                <p className="mt-3 text-sm">
                  {product.description || "No description"}
                </p>
                <div className="mt-5 flex gap-2">
                  <button
                    type="button"
                    disabled={!product.in_stock}
                    className="w-full rounded-lg bg-indigo-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-400 disabled:cursor-not-allowed disabled:opacity-50"
                  >
                    {product.in_stock ? "Add to Cart" : "Out of Stock"}
                  </button>
                  <button
                    type="button"
                    className="rounded-lg border border-current/20 px-4 py-2 text-sm font-semibold transition hover:border-indigo-400 hover:text-indigo-400"
                  >
                    Save
                  </button>
                </div>
              </div>
            </article>
          ))}

        {!loading && !error && filteredProducts.length === 0 && (
          <article className={`${panelClass} p-5`}>
            <p>No products found.</p>
          </article>
        )}
      </section>
    </div>
  );
}
