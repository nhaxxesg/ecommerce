<?php

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Comida Rápida",
 *     description="Documentación de la API del sistema de pedidos de comida rápida"
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 * @OA\Tag(name="Restaurantes", description="Operaciones de restaurantes")
 * @OA\Tag(name="Menús", description="Operaciones de menús")
 * @OA\Tag(name="Pedidos", description="Operaciones de pedidos")
 */
