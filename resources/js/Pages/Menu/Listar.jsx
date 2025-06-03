import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import MenuList from '@/Components/MenuList';

function menu() {


    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Menus
                </h2>
            }
        >
            <MenuList />
        </AuthenticatedLayout>
    );
}

export default menu;