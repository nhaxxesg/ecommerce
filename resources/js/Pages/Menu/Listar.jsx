import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import RestaurantMenu from '@/Components/RestaurantMenu';

function menu() {


    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Menus
                </h2>
            }
        >
            <RestaurantMenu />
        </AuthenticatedLayout>
    );
}

export default menu;