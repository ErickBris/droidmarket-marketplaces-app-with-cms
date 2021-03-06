package com.bk.lrandom.droidmarket;

import android.os.Bundle;
import android.support.v7.widget.Toolbar;

import com.bk.lrandom.droidmarket.fragments.ProductFragment;

public class ProductsActivity extends ActionBarParentActivity {
    Toolbar toolbar;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // TODO Auto-generated method stub
        super.onCreate(savedInstanceState);
        setContentView(R.layout.frame_layout);
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        toolbar.setTitle(R.string.app_name);
        setSupportActionBar(toolbar);
        Bundle bundle = getIntent().getExtras();
        if (bundle != null) {
            ProductFragment fragment = ProductFragment.newInstance();
            fragment.setArguments(bundle);
            getSupportFragmentManager().beginTransaction()
                    .replace(R.id.container, fragment).commit();
        }
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);
    }
}
