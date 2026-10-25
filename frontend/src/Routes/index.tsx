import { lazy, Suspense } from "react";
import { BrowserRouter, Route, Routes } from "react-router-dom";

// Layouts
import RootLayout from "../layout/RootLayout";
import AdminLayout from "../layout/AdminLayout";

// Guards
import RequireAuth from "../components/auth/RequireAuth";
import RequireAdmin from "../components/auth/RequireAdmin";
import RequireGuest from "../components/auth/RequireGuest";

// UI
import LoadingPage from "../components/ui/loadingPage";
import NotFoundPage from "../components/ui/NotFoundPage";
import RouteErrorBoundary from "../components/ui/RouteErrorBoundary";

// ============================================================
// Public
// ============================================================

const HomePage = lazy(() => import("../pages/Home"));
const ProductsPage = lazy(() => import("../pages/Shop"));
const SingleProduct = lazy(() => import("../pages/SingleProduct"));
const CategoriesPage = lazy(() => import("../pages/Category"));
const BrandsPage = lazy(() => import("../pages/Brand"));

// ============================================================
// Authenticated User
// ============================================================

const CartPage = lazy(() => import("../pages/Cart"));
const WishlistPage = lazy(() => import("../pages/Wishlist"));
const OrdersPage = lazy(() => import("../pages/Orders"));
const OrderDetailsCustomer = lazy(() => import("../pages/OrderDetails"));
const ProfilePage = lazy(() => import("../pages/Profile"));
const AddressPage = lazy(() => import("../pages/Address"));
const ContactSupportPage = lazy(() => import("../pages/Contact"));

// ============================================================
// Guest
// ============================================================

const LoginPage = lazy(() => import("../pages/Auth/login"));
const RegisterPage = lazy(() => import("../pages/Auth/register"));
const RegisterOtpPage = lazy(() => import("../pages/Auth/RegisterOtpPage"));
const OtpPage = lazy(() => import("../pages/Auth/OtpPage"));
const ForgotPasswordPage = lazy(
  () => import("../pages/Auth/ForgotPasswordPage"),
);
const ChangePasswordPage = lazy(() => import("../pages/Auth/ChangePassword"));

// ============================================================
// Admin
// ============================================================

const AdminDashboard = lazy(() => import("../pages/Admin/Dashboard"));
const AdminProducts = lazy(() => import("../pages/Admin/Products"));
const ProductDetails = lazy(() => import("../pages/Admin/ProductDetails"));
const AdminCategories = lazy(() => import("../pages/Admin/Categories"));
const AdminBrands = lazy(() => import("../pages/Admin/Brands"));
const AdminOrders = lazy(() => import("../pages/Admin/Orders"));
const AdminOrderDetails = lazy(() => import("../pages/Admin/OrderDetails"));
const AdminPayments = lazy(() => import("../pages/Admin/Payments"));
const AdminSettings = lazy(() => import("../pages/Admin/Settings"));
const UsersPage = lazy(() => import("../pages/Admin/User"));
const UserOrders = lazy(() => import("../pages/Admin/User/UserOrders"));
const AdminReviews = lazy(() => import("../pages/Admin/Reviews"));
const ReviewDetails = lazy(
  () => import("../pages/Admin/Reviews/ReviewDetails"),
);
const SupportPage = lazy(() => import("../pages/Admin/Support"));

// ============================================================
// Router
// ============================================================

const AppRouter = () => {
  return (
    <BrowserRouter>
      <RouteErrorBoundary>
        <Suspense fallback={<LoadingPage />}>
          <Routes>
            {/* ==================================================
                ADMIN
            ================================================== */}

            <Route
              path="/admin"
              element={
                <RequireAuth>
                  <RequireAdmin>
                    <AdminLayout />
                  </RequireAdmin>
                </RequireAuth>
              }
            >
              <Route index element={<AdminDashboard />} />

              <Route path="products" element={<AdminProducts />} />

              <Route path="products/:productId" element={<ProductDetails />} />

              <Route path="categories" element={<AdminCategories />} />

              <Route path="brands" element={<AdminBrands />} />

              <Route path="orders" element={<AdminOrders />} />

              <Route path="orders/:orderId" element={<AdminOrderDetails />} />

              <Route path="payments" element={<AdminPayments />} />

              <Route path="settings" element={<AdminSettings />} />

              <Route path="users" element={<UsersPage />} />

              <Route path="users/:userId/orders" element={<UserOrders />} />

              <Route path="reviews" element={<AdminReviews />} />

              <Route path="reviews/:reviewId" element={<ReviewDetails />} />

              <Route path="support" element={<SupportPage />} />
            </Route>

            {/* ==================================================
                MAIN APPLICATION
            ================================================== */}

            <Route path="/" element={<RootLayout />}>
              {/* Public */}

              <Route index element={<HomePage />} />

              <Route path="shop" element={<ProductsPage />} />

              <Route path="products/:slug" element={<SingleProduct />} />

              <Route path="categories" element={<CategoriesPage />} />

              <Route path="brands" element={<BrandsPage />} />

              {/* Authenticated */}

              <Route
                path="cart"
                element={
                  <RequireAuth>
                    <CartPage />
                  </RequireAuth>
                }
              />

              <Route
                path="orders"
                element={
                  <RequireAuth>
                    <OrdersPage />
                  </RequireAuth>
                }
              />

              <Route
                path="orders/:orderId"
                element={
                  <RequireAuth>
                    <OrderDetailsCustomer />
                  </RequireAuth>
                }
              />

              <Route
                path="profile/*"
                element={
                  <RequireAuth>
                    <ProfilePage />
                  </RequireAuth>
                }
              />

              <Route
                path="address"
                element={
                  <RequireAuth>
                    <AddressPage />
                  </RequireAuth>
                }
              />

              <Route
                path="contact"
                element={
                  <RequireAuth>
                    <ContactSupportPage />
                  </RequireAuth>
                }
              />

              <Route
                path="wishlist"
                element={
                  <RequireAuth>
                    <WishlistPage />
                  </RequireAuth>
                }
              />
            </Route>

            {/* ==================================================
                GUEST
            ================================================== */}

            <Route
              path="/login"
              element={
                <RequireGuest>
                  <LoginPage />
                </RequireGuest>
              }
            />

            <Route
              path="/register"
              element={
                <RequireGuest>
                  <RegisterPage />
                </RequireGuest>
              }
            />

            <Route
              path="/verify-otp"
              element={
                <RequireGuest>
                  <RegisterOtpPage />
                </RequireGuest>
              }
            />

            <Route
              path="/verify-password-otp"
              element={
                <RequireGuest>
                  <OtpPage />
                </RequireGuest>
              }
            />

            <Route
              path="/forget-password"
              element={
                <RequireGuest>
                  <ForgotPasswordPage />
                </RequireGuest>
              }
            />

            <Route
              path="/otp"
              element={
                <RequireGuest>
                  <OtpPage />
                </RequireGuest>
              }
            />

            <Route
              path="/reset-password"
              element={
                <RequireGuest>
                  <ChangePasswordPage />
                </RequireGuest>
              }
            />

            {/* ==================================================
                404
            ================================================== */}

            <Route path="*" element={<NotFoundPage />} />
          </Routes>
        </Suspense>
      </RouteErrorBoundary>
    </BrowserRouter>
  );
};

export default AppRouter;
