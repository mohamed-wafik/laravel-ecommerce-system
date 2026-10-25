import useFetch from "../../../hook/useAuthentienticatedQuery";
import type { IProductFilters } from "../../../hook/useProductFilters";
import { ENDPOINTS } from "../../endpoints";

export const productAPI = {
  getAll: (params: IProductFilters) =>
    useFetch({
      queryKey: [
        "products",
        `${params.brand_id}`,
        `${params.category_id}`,
        `${params.max_price}`,
        `${params.min_price}`,
        `${params.min_rating}`,
        `${params.only_discounted}`,
        `${params.page}`,
        `${params.per_page}`,
        `${params.sort}`,
      ],
      url: ENDPOINTS.GUEST.PRODUCTS.ALL,
      config: {
        params,
      },
    }),

  getTopDeals: () =>
    useFetch({
      queryKey: ["products", "top-deals"],
      url: ENDPOINTS.GUEST.PRODUCTS.TOP,
    }),
  getDetailsProduct: (slug: string) =>
    useFetch({
      queryKey: ["product", `${slug}`],
      url: ENDPOINTS.GUEST.PRODUCTS.SHOW(slug),
    }),
};
