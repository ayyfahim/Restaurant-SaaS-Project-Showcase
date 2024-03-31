import Store from "./Store";
import Home from "./Store/Home";
import Checkout from "./Store/Checkout";
import Orders from "./Account/Oders";
import TableOders from "./Account/TableOders";
import Cart from "./Account/Cart";
import DetailedView from "./Store/DetailedView";
import DetailedCategory from "./Store/DetailedCategoryView";
import Login from "./Account/Login";
import AccountView from "./Account/View";
import AccountEdit from "./Account/Edit";
import AccountLanguage from "./Account/Language";
import AccountAllergens from "./Account/Allergens";
import RECEIPTS from "./Account/Receipts";
import PAY from "./Account/Pay";
import Register from "./Account/Register";
import Forgot_Password from "./Account/Forgot-Password";
import ChangePassword from "./Account/ChangePassword";
import Card from "./Account/Card";
import Support from "./Account/Support";

export default {
    HOME: Home,
    STORE: Store,
    CART: Cart,
    Checkout: Checkout,
    ORDERS: Orders,
    TableOrders: TableOders,
    DETAILED_VIEW: DetailedView,
    CATEGORY_DETAIL: DetailedCategory,
    LOGIN: Login,
    REGISTER: Register,
    AccountView: AccountView,
    AccountEdit: AccountEdit,
    AccountLanguage: AccountLanguage,
    AccountAllergens: AccountAllergens,
    RECEIPTS: RECEIPTS,
    PAY: PAY,
    CARD: Card,
    FORGOT: Forgot_Password,
    ChangePassword: ChangePassword,
    Support: Support
};
