console.log(`Compilando site ${process.env.site}.....`)


// load site-specific config
if (process.env.site) {
    require(`${__dirname}/webpack.mix.${process.env.site}.js`);
} else {
    console.error('Site no encontrado');
}
